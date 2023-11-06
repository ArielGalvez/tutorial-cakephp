<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Event\EventInterface;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Validation\Validator;

/**
 * Articles Model
 *
 * @property \App\Model\Table\TagsTable&\Cake\ORM\Association\BelongsToMany $Tags
 * @method \App\Model\Entity\Article newEmptyEntity()
 * @method \App\Model\Entity\Article newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Article[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Article get($primaryKey, $options = [])
 * @method \App\Model\Entity\Article findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Article patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Article[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Article|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Article saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ArticlesTable extends Table
{
    /**
     * Initialize the table and add timestamp behavior.
     *
     * @param array $config Additional table configuration.
     * @return void
     */
    public function initialize(array $config): void
    {
        $this->addBehavior('Timestamp');
        $this->belongsToMany('Tags', [
            'joinTable' => 'articles_tags',
            'dependent' => true,
        ]);
    }

    /**
     * Before saving an entity, generate a slug if it doesn't exist.
     *
     * @param \Cake\Event\EventInterface $event Save event.
     * @param \Cake\Datasource\EntityInterface $entity Entity being saved.
     * @param array $options Additional options.
     * @return void
     */
    public function beforeSave(EventInterface $event, $entity, $options)
    {
        if ($entity->has('tags')) {
            $entity->set('tags', $this->_buildTags($entity->tag_string ?? ''));
        }

        if ($entity->isNew() && !$entity->get('slug')) {
            $sluggedTitle = Text::slug($entity->get('title'));
            // trim slug to maximum length defined in schema
            $entity->set('slug', substr($sluggedTitle, 0, 191));
        }
    }

    /**
     * Define default validation rules for the articles entity.
     *
     * @param \Cake\Validation\Validator $validator Validator to be used.
     * @return \Cake\Validation\Validator The configured validator.
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->notEmptyString('title')
            ->minLength('title', 10)
            ->maxLength('title', 255)
            ->add('title', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => 'The title is already in use',
            ])
            ->notEmptyString('body')
            ->minLength('body', 10);

        return $validator;
    }

    /**
     * Custom finder method to retrieve articles based on provided tags.
     *
     * @param \Cake\ORM\Query $query The query builder.
     * @param array $options An array of options containing the 'tags' key with an array of tags.
     * @return \Cake\ORM\Query A Query object with conditions for retrieving tagged articles.
     */
    public function findTagged(Query $query, array $options)
    {
        $columns = [
            'Articles.id', 'Articles.user_id', 'Articles.title',
            'Articles.body', 'Articles.published', 'Articles.created',
            'Articles.slug',
        ];

        $query = $query
            ->select($columns)
            ->distinct($columns);

        if (empty($options['tags'])) {
            // If there are no tags provided, find articles that have no tags.
            $query->leftJoinWith('Tags')
                ->where(['Tags.title IS' => null]);
        } else {
            // Find articles that have one or more of the provided tags.
            $query->innerJoinWith('Tags')
                ->where(['Tags.title IN' => $options['tags']]);
        }

        return $query->group(['Articles.id']);
    }

    /**
     * Build and return an array of tags from a comma-separated tag string.
     *
     * This method takes a comma-separated string of tags, trims each tag, removes empty tags,
     * eliminates duplicated tags, and returns an array of corresponding Tag entities.
     * If the tags already exist in the database, they are retrieved; otherwise, new Tag entities are created.
     *
     * @param string $tagString The comma-separated tag string.
     * @return \Cake\Datasource\EntityInterface[] An array of Tag entities.
     */
    protected function _buildTags($tagString)
    {
        // Trim tags
        $newTags = array_map('trim', explode(',', $tagString));
        // Remove all empty tags
        $newTags = array_filter($newTags);
        // Reduce duplicated tags
        $newTags = array_unique($newTags);

        $out = [];
        $tags = $this->Tags->find()
            ->where(['Tags.title IN' => $newTags])
            ->all();

        // Remove existing tags from the list of new tags.
        foreach ($tags->extract('title') as $existing) {
            $index = array_search($existing, $newTags);
            if ($index !== false) {
                unset($newTags[$index]);
            }
        }
        // Add existing tags.
        foreach ($tags as $tag) {
            $out[] = $tag;
        }
        // Add new tags.
        foreach ($newTags as $tag) {
            $out[] = $this->Tags->newEntity(['title' => $tag]);
        }

        return $out;
    }
}
