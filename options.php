<?php
global $DDLeetSpeak;

// Create Option HTML
$option_html_a_m = '';
$option_html_n_z = '';
$count = 1;
foreach($DDLeetSpeak->getLeetCharacters() as $alpha_char => $leet_chars) {
    $menu = '<select size="1" onchange="leetCharacterSelected(this);"><option value="-1">Suggestions</option>';
    foreach($leet_chars as $leet_char) {
        $menu .= '<option value="'.str_replace('"', '\"', $leet_char).'">'.htmlspecialchars($leet_char).'</option>';
    }
    $menu .= '</select>';
    $html = '<tr valign="top">
                    <th scope="row" style="width:15px"><strong>'.strtoupper($alpha_char).'</strong></th>
                    <td><input type="text" style="width:50px" maxlength="10" name="ddleetspeak_'.$alpha_char.'" value="'.htmlspecialchars(str_replace('"', '\"', get_option('ddleetspeak_'.$alpha_char))).'" /> <= '.$menu.'</td>
                </tr>';
    if($count <= 13) {
        $option_html_a_m .= $html;
    } else {
        $option_html_n_z .= $html;
    }
    $count++;
}
?>
<script type="text/javascript" language="Javascript">
    function leetCharacterSelected(select_menu) {
        if(select_menu.value == -1) return;
        var input = select_menu.parentNode.parentNode.getElementsByTagName('input')[0];
        input.value = select_menu.value;
        select_menu.options[0].selected = true;
    }
</script>
<div class="wrap">
    <h2>Leet Speak Admin => <?= $DDLeetSpeak->leetize('Leet Speak Admin'); ?> </h2>
    <form method="post" action="options.php">
        <?php settings_fields('ddleetspeak-option-group'); ?>
        <h3>Alpha Character Translations</h3>
        <div style="clear:both"></div>
        <div style="width:250px;float:left;">
            <table class="form-table">
                <?= $option_html_a_m; ?>
            </table>
        </div>
        <div style="width:250px;float:left;">
            <table class="form-table">
                <?= $option_html_n_z; ?>
            </table>
        </div>
        <div style="clear:both"></div>
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </p>
    </form>
</div>