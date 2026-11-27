<a href="<?php echo url('menu/detail', array('id' => $m['id'])); ?>" class="card item-menu" data-tag="<?php echo e($m['filter_tag']); ?>" data-name="<?php echo e(strtolower($m['nama_menu'])); ?>">
    <?php if (menu_terlaris($m['id'])): ?><span class="card-terlaris">Terlaris</span><?php endif; ?>
    <img src="<?php echo e($m['gambar_url']); ?>" alt="<?php echo e($m['nama_menu']); ?>" class="card-img" />
    <div class="card-body">
        <div class="card-name"><?php echo e($m['nama_menu']); ?></div>
        <div class="card-desc"><?php echo e($m['grup_menu']); ?></div>
        <div class="card-bottom">
            <span class="price"><?php echo harga_kartu_html($m); ?></span>
            <span class="card-more">Detail &rarr;</span>
        </div>
    </div>
</a>
