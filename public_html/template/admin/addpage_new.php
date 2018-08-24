<form class="vg-wrap vg-element vg-ninteen-of-twenty" method="post" action="<?=PATH.ADMIN_PATH?>/add"
      enctype="multipart/form-data">
    <div class="vg-wrap vg-element vg-full">
        <div class="vg-wrap vg-element vg-full vg-firm-background-color4 vg-box-shadow">
        <div class="vg-element vg-half vg-left">
                <div class="vg-element">
                    <input type="submit" class="vg-text vg-firm-color1 vg-firm-background-color4 vg-input vg-button" value="Сохранить">
                </div>
                <div class="vg-element">
                    <input type="submit" class="vg-text vg-firm-color1 vg-firm-background-color4 vg-input vg-button" value="Удалить">
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="table" value="<?=$table?>">

    <?php
    foreach($columns as $class => $block){
        echo '<div class="vg-wrap vg-element ' . $class . '">';
        if($class == 'vg-second') {
            echo '<div class="vg-firm-background-color4 vg-box-shadow">';
        }

        foreach ($block as $row) {
            foreach ($templateArr as $type => $items) {
                if (in_array($row, $items)) {
                    if (!@include PATH . ADMIN_TEMPLATE . '/include/form_templates/' . $type . '.php') {
                        throw new \core\base\controller\ContrException('Не найден шаблон ' . PATH . ADMIN_TEMPLATE . '/include/form_templates/' . $type . '.php');
                    }
                    break;
                }
            }
        }
        echo '</div>';
        if($class == 'vg-second') {
            echo '</div>';
        }
    }
    ?>

    <div class="vg-wrap vg-element vg-full">
        <div class="vg-wrap vg-element vg-full vg-firm-background-color4 vg-box-shadow">
        <div class="vg-element vg-half vg-left">
                <div class="vg-element">
                    <input type="submit" class="vg-text vg-firm-color1 vg-firm-background-color4 vg-input vg-button" value="Сохранить">
                </div>
                <div class="vg-element">
                    <input type="submit" class="vg-text vg-firm-color1 vg-firm-background-color4 vg-input vg-button" value="Удалить">
                </div>
            </div>
        </div>
    </div>
</form>