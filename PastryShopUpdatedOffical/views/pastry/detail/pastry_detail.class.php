<?php
/**
 * Author: Kierra White
 * Date: 11/21/2024
 * File: pastry_detail.class.php
 * Description:
 */
class PastryDetail extends PastryIndexView{
    public function display($pastry, $confirm =""):void{
        parent::displayHeader("Pastry Details");

        //retrieving the details
        $id = $pastry->getPastryId();
        $name = $pastry->getName();
        $description = $pastry->getDescription();
        $price = $pastry->getPrice();
        $categoryId = $pastry->getCategoryId();
        $availability = $pastry->isInMenu();
        $image = $pastry->getImagePath();
        $stock = $pastry->getStockQuantity();

        if (strpos($image, "http://") === false AND strpos($image, "https://") === false) {
            $image = BASE_URL . '/' . MOVIE_IMG . $image;
        }
        ?>
        <h1>Pastry Details</h1>
        <table>
            <tr>
                <td>
                    <img src="<?= $image ?>" alt="<?= $name ?>" />
                </td>
                <td>
                    <p>Name:</p>
                    <p>Price:</p>
                    <p>Availability:</p>
                    <p>Stock:</p>
                    <p>Description:</p>
                </td>
                <td>
                    <p><?= $name ?></p>
                    <p><?= $price?></p>
                    <p><?= $availability?></p>
                    <p><?= $stock?></p>
                    <p><?= $description?></p>
                    <div><?= $confirm ?></div>
                </td>
            </tr>
        </table>
        <a href="<?= BASE_URL ?>/pastry/menu">Go to Menu</a>
        <?php
        //for the footer
        parent::displayFooter();

    }

}