<div class="row">
    <?php $lister->start("deneme"); ?>
    <div class="col-3">
        <div class="card">
            <img
                src="{resim}"
                class="card-img-top"
                alt="{metin/0-30}"
            />
            <div class="card-body">
                <h5 class="card-title">{metin/0-30}</h5>
                <p class="card-text">
                    {metin/0-80}...
                </p>
                <a href="index.php?sk={sayi}-{id}" class="btn btn-primary">See More</a>
                <button type="button" data-uniqe="{sayi}-{id}" data-event="click" data-type="reload" class="btn btn-primary detail">See More</button>
            </div>
        </div>
    </div>
    <?php $lister->end(); ?>
</div>