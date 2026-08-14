<?php $pager->setSurroundCount(2) ?>
<nav aria-label="Page navigation" class="mt-auto flex justify-center md:justify-between items-center w-full overflow-x-auto custom-scrollbar">
    <ul class="flex items-center gap-1 w-max mx-auto md:mx-0 py-3 px-4 md:px-6">
        <?php if ($pager->hasPrevious()) : ?>
            <li>
                <a href="<?= $pager->getPrevious() ?>" class="px-3 py-1.5 rounded border border-outline-variant text-on-surface hover:bg-surface-container-high transition-colors text-sm font-medium whitespace-nowrap">&laquo; Prev</a>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li>
                <a href="<?= $link['uri'] ?>" class="px-3 py-1.5 rounded border text-sm font-medium transition-colors whitespace-nowrap <?= $link['active'] ? 'bg-primary text-on-primary border-primary' : 'border-outline-variant text-on-surface hover:bg-surface-container-high' ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <li>
                <a href="<?= $pager->getNext() ?>" class="px-3 py-1.5 rounded border border-outline-variant text-on-surface hover:bg-surface-container-high transition-colors text-sm font-medium whitespace-nowrap">Next &raquo;</a>
            </li>
        <?php endif ?>
    </ul>
</nav>