<!-- app/Views/pagination/custom_pager.php -->
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        <!-- Display the current page and total page count -->
        <li class="page-item disabled">
            <span class="page-link">Page <?= $pager->getCurrentPageNumber() ?> of <?= $pager->getPageCount() ?></span>
        </li>

        <!-- First button -->
        <?php if ($pager->hasPrevious()) : ?>
            <li class="page-item">
                <a href="<?= $pager->getFirst() ?>" class="page-link" aria-label="First">
                    <span aria-hidden="true">First</span>
                </a>
            </li>
        <?php endif; ?>

        <!-- Previous button -->
        <?php if ($pager->hasPrevious()) : ?>
            <li class="page-item">
                <a href="<?= $pager->getPrevious() ?>" class="page-link" aria-label="Previous">
                    <span aria-hidden="true">Previous</span>
                </a>
            </li>
        <?php endif; ?>

        <!-- Page numbers -->
        <?php
        $currentPage = $pager->getCurrentPageNumber();
        $totalPages = $pager->getPageCount();
        $range = 2; // Number of pages to show before and after the current page

        // Calculate start and end page numbers to display
        $start = max(1, $currentPage - $range);
        $end = min($totalPages, $currentPage + $range);

        // Show "First" page if we're beyond the starting pages
        if ($start > 1) : ?>
            <li class="page-item">
                <a class="page-link" href="<?= $pager->get(1) ?>">1</a>
            </li>
            <?php if ($start > 2) : ?>
                <li class="page-item disabled"><span class="page-link">...</span></li>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Loop through the page range -->
        <?php for ($i = $start; $i <= $end; $i++) : ?>
            <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                <a class="page-link" href="<?= $pager->get($i) ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <!-- Show "..." after current page range if there are more pages -->
        <?php if ($end < $totalPages) : ?>
            <?php if ($end < $totalPages - 1) : ?>
                <li class="page-item disabled"><span class="page-link">...</span></li>
            <?php endif; ?>
            <li class="page-item">
                <a class="page-link" href="<?= $pager->get($totalPages) ?>"><?= $totalPages ?></a>
            </li>
        <?php endif; ?>

        <!-- Next button -->
        <?php if ($pager->hasNext()) : ?>
            <li class="page-item">
                <a href="<?= $pager->getNext() ?>" class="page-link" aria-label="Next">
                    <span aria-hidden="true">Next</span>
                </a>
            </li>
        <?php endif; ?>

        <!-- Last button -->
        <?php if ($pager->hasNext()) : ?>
            <li class="page-item">
                <a href="<?= $pager->getLast() ?>" class="page-link" aria-label="Last">
                    <span aria-hidden="true">Last</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
