<ul>
    <?php foreach ($attachments as $v):?>
        <li><a href="http://member.js-exp.com/public/upload/fbaAnnex/<?=$v['fbaId']?>/<?=$v['filePath']?>" target="_blank"><?=$v['fileName']?></a></li>
    <?php endforeach;?>
</ul>