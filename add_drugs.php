<?php
session_start();
include "connection.php";

include "functions.php";
$errors_array = array();
$success_array = array();
$user_id = $_SESSION["user_id"];

if (!isset($_SESSION['loggedIn'])) {
    header("location: login.php");
} else {
    if (isset($_POST["saveMedicine"])) {

        try {

            //store form values in variables
            $medicineName = trim($_POST["medicine_name"]);
            $dosageQty = intval(trim($_POST["dosage_quantity"]));
            $dosageUnit = trim($_POST["dosage_unit"]);
            $milligramQty = intval(trim($_POST["milligram_quantity"]));
            $milligramUnit = trim($_POST["milligram_unit"]);
            $frequencyQty = intval(trim($_POST["frequency_quantity"]));
            $frequencyUnit = trim($_POST["frequency_unit"]);

            $query = "INSERT INTO medicine (medicine_name, dosage_quantity, dosage_unit, milligram_quantity, milligram_unit, frequency_quantity, frequency_unit, user_ID)
                VALUES (?,?,?,?,?,?,?,?);";

            if ($statement = $con->prepare($query)) {
                if ($statement->bind_param("sisisisi", $medicineName, $dosageQty, $dosageUnit, $milligramQty, $milligramUnit, $frequencyQty, $frequencyUnit, $user_id)) {

                    if ($statement->execute()) {

                        $success_array[] = "Medicine Saved Succesfully";
                    } else {
                        $errors_array[] = "Error Saving Medicine";
                    }
                } else {
                    $errors_array[] = "Internal Server Error";
                }
            } else {
                $errors_array[] = "Internal Server Error";

            }

        } catch (Exception $ex) {
            $errors_array[] = $ex->getMessage();
        }
    }
}

?>

<?php set_header("Medicine");?>
<div class="container">
    <div class="row mt-5">

        <div class="col-md-4 ">
            <h4 class="mb-3 text-center">Save a new medicine</h4>

            <?php
if (isset($errors_array) && !empty($errors_array)) {
    foreach ($errors_array as $alert) {
        echo '  <div class="alert alert-danger" role="alert">
                ' . $alert . '
                </div>';

    }
}

if (isset($success_array) && !empty($success_array)) {
    foreach ($success_array as $msg) {
        echo '  <div class="alert alert-success" role="alert">
                ' . $msg . '
                </div>';

    }
}

?>
            <form action="" method="post">
                <div class="form-group">
                    <label for="">Medicine Name</label>
                    <input type="text" name="medicine_name" id="medicine_name" placeholder="Medicine Name" required
                        class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Dosage Quantity</label>
                    <input type="number" name="dosage_quantity" id="dosage_quantity" required class="form-control"
                        min="1" max="300" value="1">
                </div>
                <div class="form-group">
                    <label for="">Dosage Unit</label>
                    <select name="dosage_unit" id="dosage_unit" class="form-select" required>
                        <option value="Tab" selected>Tab</option>
                        <option value="Bottle">Bottle</option>
                        <option value="Syringe/Injection">Syringe/Injection</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Milligrams</label>
                    <input type="text" name="milligram_quantity" id="milligram" placeholder="Milligram" required
                        class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Unit(g/mg)</label>
                    <select name="milligram_unit" id="unit" class="form-select" required>
                        <option value="Grams" selected>Grams</option>
                        <option value="MilliGrams">MilliGrams</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="">Frequency Quantity</label>
                    <input type="number" name="frequency_quantity" id="frequency_quantity"
                        placeholder="Frequency Quantity" required class="form-control" min="1" max="300" value="1">
                </div>

                <div class="form-group">
                    <label for="">Frequency Unit</label>
                    <select name="frequency_unit" id="frequency_unit" class="form-select">
                        <option value="Daily" selected>Daily</option>
                        <option value="Weekly">Weekly</option>
                        <option value="Montly">Monthly</option>
                    </select>
                </div>
                <input type="submit" name="saveMedicine" value="Click to save"
                    class="form-control btn btn-primary col-12 my-3">
            </form>

        </div>

        <div class="col-md-8">
            <h5 class="">Saved Medicine</h5>
            <div class="table-responsive">
                <table class="table table-striped table-light">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Dosage Qty.</th>
                            <th>Dosage Unit</th>
                            <th>Measurement Qty.</th>
                            <th>Measurement Unit</th>
                            <th>Frequency Qty.</th>
                            <th>Frequency Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
$query = "SELECT * FROM medicine WHERE user_ID = " . $_SESSION['user_id'] . "";
$result = mysqli_query($con, $query);

if ($result) {
    $count = 1;
    while ($rows = mysqli_fetch_array($result)) {
        echo '
                      <tr>
                        <td>' . $rows['medicine_name'] . '</td>
                        <td>' . $rows['dosage_quantity'] . '</td>
                        <td>' . $rows['dosage_unit'] . '</td>
                        <td>' . $rows['milligram_quantity'] . '</td>
                        <td>' . $rows['milligram_unit'] . '</td>
                        <td>' . $rows['frequency_quantity'] . '</td>
                        <td>' . $rows['frequency_unit'] . '</td>

                    </tr>
                ';
        $count += 1;
    }
} else {
    echo "<h1>MEDICINES NOT FOUND</h1>";
}
?>
                    </tbody>
                </table>
            </div>


        </div>
    </div>
</div>

<?php set_footer()?>