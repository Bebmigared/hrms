{
    while($row = mysqli_fetch_assoc($result)) {
      $data_item[] = $row;
    }
$it = $data_item[$h]['item_quantity'];
    for ($h = 0; $h < count($data_item); $h++)
    { 
$query = "UPDATE items SET opening_quantity = $it";
if (mysqli_query($conn, $query)) {
    echo "Record updated successfully";
  } else {
    echo "Error updating record: " . mysqli_error($conn);
  }
    }