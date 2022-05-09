<section style="display:flex; justify-content:center;">

    <div style="width:700px;padding:10px">

        <?php foreach($tags as $tag) : ?>
            <a style="font-size:2.5rem;margin: 10px" href="/oren/video/get_batch_videos_by_tag/<?php echo $tag ?>" title="">#<?php echo $tag ?></a>
        <?php endforeach ; ?>
    </div>
</section>