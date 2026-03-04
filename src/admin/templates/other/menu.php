<?php
    session_start();
    $index = isset($_POST['index']) ? (int) $_POST['index'] : null;
    $elements = isset($_SESSION['user_data']['perms']) ? $_SESSION['user_data']['perms'] : null;
    
    if($elements != null){
        foreach($elements as $key => $element){
            $is_selected = $key === $index ? true : false;
            $js = $element->type == 'js' ? $element->url : null;

            if($js == null){
?> 
            
            <li <?php echo $is_selected ? 'class="selected"' : ''; ?>>
                <i class="fa fa-circle" aria-hidden="true"></i>&nbsp;
                <a href="<?php echo !$is_selected 
                    ? htmlspecialchars($element->url, ENT_QUOTES, 'UTF-8') 
                    : '#'; ?>">
                    <?php echo htmlspecialchars($element->text, ENT_QUOTES, 'UTF-8'); ?>
                </a>
            </li>
<?php               
            }
            else{
?> 
            
            <li <?php echo 'onclick='.htmlspecialchars($element->url, ENT_QUOTES, 'UTF-8'); ?>>
                <i class="fa fa-circle" aria-hidden="true"></i>&nbsp;
                <a><?php echo htmlspecialchars($element->text, ENT_QUOTES, 'UTF-8'); ?></a>
            </li>
<?php
            }
        }
    }
?>