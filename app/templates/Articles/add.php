<h1>Add Article</h1>
<?php
    echo $this->Form->create($article);
    // TODO: user_id Hard code for now. we going to fix it soon.
    echo $this->Form->control('user_id', ['type' => 'hidden', 'value' => 2]);
    echo $this->Form->control('title');
    echo $this->Form->control('body', ['rows' => '3']);
    echo $this->Form->button(__('Save Article'));
    echo $this->Form->end();
