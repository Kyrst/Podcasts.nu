<?php if ( $total_pages > 1 ): ?>
	<?php $range = 5 ?>

	<ul class="pagination">
		<li<?php if ( $paginator->getCurrentPage() === 1 ): ?> class="disabled"<?php endif ?>><a href="javascript:" data-page="<?= $paginator->getCurrentPage() - 1 ?>">&laquo;</a></li>

		<?php for ( $page = ($paginator->getCurrentPage() - $range), $num = (($paginator->getCurrentPage() + $range) + 1); $page < $num; ++$page ): ?>
			<?php if (($page > 0) && ($page <= $total_pages)): ?>
				<li id="pagination_page_<?= $page ?>"<?php if ( $paginator->getCurrentPage() === $page ): ?> class="active"<?php endif ?>><a href="javascript:" data-page="<?= $page ?>"><?= $page ?></a></li>
			<?php endif ?>
		<?php endfor ?>

		<li<?php if ( $paginator->getCurrentPage() == $total_pages ): ?> class="disabled"<?php endif ?>><a href="javascript:" data-page="<?= $paginator->getCurrentPage() + 1 ?>">&raquo;</a></li>
	</ul>
<?php endif ?>