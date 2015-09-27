<!-- File: src/Template/Articles/view.ctp -->
<h1><?= h($user->title) ?></h1>
<p><?= h($user->body) ?></p>
<p><small>Created: <?= $user->created->format(DATE_RFC850) ?></small></p>