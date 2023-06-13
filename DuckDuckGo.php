<?php
    $kw = isset($_POST["input"]) ? $_POST["input"] : "";
    $url = 'https://duckduckgo.com/ac/?q=' . urlencode($kw) . '&kl=wt-wt';
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    function get_csv_data($keywords) {
        $header = array('KeyWords');
        $rows = array($header);
    
        foreach ($keywords as $keyword) {
            $rows[] = array($keyword);
        }
    
        $output = fopen('php://temp', 'w');
        foreach ($rows as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csv_data = stream_get_contents($output);
        fclose($output);
    
        return 'data:text/csv;charset=utf-8,' . urlencode($csv_data);
    }
    // Extract the keywords into an array
    $keywords = array_column($data, 'phrase');
?>

<!DOCTYPE html>
<html>
<head>
	<title>Keyword Suggestions</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="style.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
	<div class="container">
		<h1>Keyword Suggestions</h1>
		<form method="post">
			<input type="text" name="input" placeholder="Type a search term..." value="<?php echo htmlspecialchars($kw); ?>">
			<input type="submit" value="Search">
		</form>
		<?php if (!empty($data)): ?>
			<table>
				<thead>
					<tr>
						<th>Suggestion</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($data as $item): ?>
						<tr>
							<td><?php echo htmlspecialchars($item["phrase"]); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<div class="actions">
				<button class="btn copy" onclick="copyKeywords()">Copy to Clipboard</button>
				<a class="btn export" href="<?php echo get_csv_data($keywords); ?>" download="DuckDuckGo keywords.csv">Export as CSV</a>
                <button class="btn more" onclick="location.reload()">Get More</button>
				<a href="./"><button class="btn different">Try Different Search Engine</button></a>
            </div>
		<?php endif; ?>
	</div>
<footer>
  <div class="footer-container">
    <div class="footer-made-by">
      <p>Made with <span class="heart">&hearts;</span> by Amr Achraf</p>
    </div>
        <a href="http://facebook.com/amrachraf6690" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-facebook fa-beat" style="color: #666666;"></i></a>
        <a href="http://linkedin.com/in/amrachraf6690" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-linkedin fa-beat" style="color: #666666;"></i></a>
        <a href="http://wa.me/+201028751528" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-whatsapp fa-beat" style="color: #666666;"></i></a>
        <a href="http://github.com/amrachraf6690" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-github fa-beat" style="color: #666666;"></i></a>
  </div>
</footer>
	<script>
		function copyKeywords() {
			var keywords = "<?php echo implode(' , ', $keywords); ?>";
			var input = document.createElement('input');
			input.setAttribute('value', keywords);
			document.body.appendChild(input);
			input.select();
			document.execCommand('copy');
			document.body.removeChild(input);
            alert('Copied To ClipBoard')
		
    }
	</script>
</body>
</html>