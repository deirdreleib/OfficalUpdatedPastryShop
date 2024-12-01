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
        <div class="pastry-details-container">
            <h1>Pastry Details</h1>
            <div class="pastry-details">
                <!-- Pastry Image -->
                <div class="pastry-image">
                    <img src="<?= $image ?>" alt="<?= htmlspecialchars($name)?>">
                </div>

                <!-- Pastry Info -->
                <div class="pastry-info">
                    <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
                    <p><strong>Price:</strong> $<?= number_format($price, 2) ?></p>
                    <p><strong>Availability:</strong> <?= htmlspecialchars($availability) ?></p>
                    <p><strong>Stock:</strong> <?= htmlspecialchars($stock) ?></p>
                    <p><strong>Description:</strong> <?= htmlspecialchars($description) ?></p>
                    <div class="confirmation-message"><?= $confirm ?></div>
                </div>
            </div>
            <!-- Add to Cart Button -->

            <!-- Navigation Link -->
            <a href="<?= BASE_URL ?>/pastry/menu" class="btn-primary">Go to Menu</a>
        </div>
        <?php
        //for the footer
        parent::displayFooter();

    }

}