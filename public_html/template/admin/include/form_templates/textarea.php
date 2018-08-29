<?php if(strpos($row, 'content') !== false):?>
    <div class="vg-wrap vg-element vg-full vg-firm-background-color4 vg-box-shadow">
        <div class="vg-wrap vg-element vg-full vg-box-shadow">
            <div class="vg-wrap vg-element vg-full">
                <div class="vg-element vg-full vg-left">
                    <span class="vg-header"><?php echo $translate[$row][0] ? $translate[$row][0] : $row?></span>
                </div>
                <div class="vg-element vg-full vg-left">
                    <span class="vg-text vg-firm-color5"><?=$translate[$row][1]?></span><span class="vg_subheader"></span>
                </div>
            </div>
            <div class="vg-element vg-full vg-left">
                <textarea name="<?=$row?>" class="vg-input vg-text vg-full vg-firm-color1"><?php echo isset($_SESSION['res'][$row]) ? htmlspecialchars($_SESSION['res'][$row]) : htmlspecialchars($data[$row])?></textarea>
            </div>
        </div>
    </div>
<?php else:?>
    <div class="vg-wrap vg-element vg-full vg-box-shadow">
        <div class="vg-wrap vg-element vg-full vg-box-shadow">
            <div class="vg-wrap vg-element vg-full">
                <div class="vg-element vg-full vg-left">
                    <span class="vg-header"><?php echo $translate[$row][0] ? $translate[$row][0] : $row?></span>
                </div>
                <div class="vg-element vg-full vg-left">
                    <span class="vg-text vg-firm-color5"><?=$translate[$row][1]?></span><span class="vg_subheader"></span>
                </div>
            </div>
            <div class="vg-element vg-full">
                <div class="vg-element vg-full vg-left">
                    <textarea name="<?=$row?>" class="vg-input vg-text vg-full vg-firm-color1"><?php echo isset($_SESSION['res'][$row]) ? htmlspecialchars($_SESSION['res'][$row]) : htmlspecialchars($data[$row])?></textarea>
                </div>
            </div>
        </div>
    </div>
<?php endif;?>
