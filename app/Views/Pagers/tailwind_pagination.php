<?php $pager->setSurroundCount(2) ?>
<nav aria-label="Page navigation" class="mt-4 flex justify-between items-center px-6 py-3 border-t border-outline-variant bg-surface-container-lowest">
    <ul class="flex items-center gap-1">
        <?php if ($pager->hasPrevious()) : ?>
            <li>
                <a href="<?= $pager->getPrevious() ?>" class="px-3 py-1.5 rounded border border-outline-variant text-on-surface hover:bg-surface-container-high transition-colors text-sm font-medium">&laquo; Prev</a>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li>
                <a href="<?= $link['uri'] ?>" class="px-3 py-1.5 rounded border text-sm font-medium transition-colors <?= $link['active'] ? 'bg-primary text-on-primary border-primary' : 'border-outline-variant text-on-surface hover:bg-surface-container-high' ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <li>
                <a href="<?= $pager->getNext() ?>" class="px-3 py-1.5 rounded border border-outline-variant text-on-surface hover:bg-surface-container-high transition-colors text-sm font-medium">Next &raquo;</a>
            </li>
        <?php endif ?>
    </ul>
</nav>