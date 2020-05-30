<head>
<meta charset="utf-8">
<title>Calculator</title>
</head>
<body>
<form method="post" attribute="post" action="calculator.php">
<p>First Value:<br/>
<input type="text" id="first" name="first"></p>
<p>Second Value:<br/>
<input type="text" id="second" name="second"></p>
 <tr>
        <td>Select Oprator</td>
        <td><select name="op">
            <option value="+">+</option>
            <option value="-">-</option>
            <option value="*">*</option>
            <option value="/">/</option>
        </select></td>
 </tr>
<p></p>
<button type="submit" name="answer" id="answer" value="answer">Calculate</button>
</form>

<p>The answer is: 
<?php
$first = $_POST['first'];
$second= $_POST['second'];
if($_POST['op'] == '+') {
echo $first + $second;
}
else if($_POST['op'] == '-') {
echo $first - $second;
}
else if($_POST['op'] == '*') {
echo $first * $second;
} 
else if($_POST['op'] == '/') {
echo $first / $second;
}
?>
</p> 



</body>
</html>
