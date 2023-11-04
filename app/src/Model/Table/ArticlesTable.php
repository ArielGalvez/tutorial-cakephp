<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Event\EventInterface;
use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Validation\Validator;

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
}
