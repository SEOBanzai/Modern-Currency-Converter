<?php
$currencies = json_decode(file_get_contents('currencies.json'), true);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Currency Converter</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="card shadow p-4">
    <h2 class="mb-4 text-center">Currency Converter</h2>
    <form id="converterForm" class="row g-3">
      <div class="col-md-4">
        <label class="form-label">From</label>
        <select name="from" class="form-select" required>
          <?php foreach ($currencies as $code => $name): ?>
            <option value="<?= $code ?>"><?= $code ?> - <?= $name ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">To</label>
        <select name="to" class="form-select" required>
          <?php foreach ($currencies as $code => $name): ?>
            <option value="<?= $code ?>"><?= $code ?> - <?= $name ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Amount</label>
        <input type="number" name="amount" step="0.01" min="0" class="form-control" placeholder="e.g. 100" required>
      </div>
      <div class="col-12 text-center mt-3">
        <button type="submit" class="btn btn-primary px-5">Convert</button>
      </div>
    </form>
    <div id="result" class="alert alert-info text-center mt-4 d-none"></div>
  </div>
</div>

<script>
document.getElementById("converterForm").addEventListener("submit", function(e) {
  e.preventDefault();
  const formData = new FormData(this);

  axios.post("convert.php", formData)
    .then(response => {
      const resultDiv = document.getElementById("result");
      if (response.data.success) {
        resultDiv.classList.remove("d-none", "alert-danger");
        resultDiv.classList.add("alert-info");
        resultDiv.innerHTML = `<strong>Result:</strong> ${response.data.converted}`;
      } else {
        resultDiv.classList.remove("d-none", "alert-info");
        resultDiv.classList.add("alert-danger");
        resultDiv.innerHTML = "Conversion failed.";
      }
    })
    .catch(error => {
      document.getElementById("result").classList.remove("d-none");
      document.getElementById("result").classList.add("alert-danger");
      document.getElementById("result").innerText = "Error contacting server.";
    });
});
</script>
</body>
</html>
