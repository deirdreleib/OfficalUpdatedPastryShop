<?php
/**
 * Author: Kierra White
 * Date: 11/21/2024
 * File: pastry_index.class.php
 * Description:
 */
class PastryIndex extends PastryIndexView{
    public function display($pastries):void
    {
        //display page header
        parent::displayHeader("List All Pastries");
        ?>
        <div>
            <?php
            if ($pastries === 0) {
                echo "No Pastry was found.<br><br><br><br><br>";
            } else {
                foreach ($pastries as $pastry) {
                    $id = $pastry->getPastryId();
                    $name = $pastry->getName();
                    $price = $pastry->getPrice();
                    $availability = $pastry->isInMenu();
                    $image = $pastry->getImagePath();
                    if (strpos($image, "http://") === false AND strpos($image, "https://") === false) {
                        $image = BASE_URL . "/" . PASTRY_IMG . $image;
                    }

                    echo "<div><p><a href='", BASE_URL, "/pastry/detail/$id'><img src='" . $image .
                        "'></a><span>$name<br>Price: $price<br>" . $availability. "</span></p></div>";

                }
            }
            ?>
        </div>


        <?php
        //display page footer
        parent::displayFooter();
    }
}