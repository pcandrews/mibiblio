<!DOCTYPE html>
<html>
<body>

<p>The best way to loop through an array is using a standard for loop:</p>

<button onclick="myFunction()">Try it</button>

<p id="demo"></p>

<script>

function showUser(str) {
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","ajaxTest2.php?q="+str,true);
        xmlhttp.send();
    }
}

function myFunction() {
    var index;
    var text = "<ul>";
    var fruits = ["Banana", "Orange", "Apple", "Mango"];
    for (index = 0; index < fruits.length; index++) {
        text += "<li>" + fruits[index] + "</li>";
    }
    text += "</ul>";
    document.getElementById("demo").innerHTML = text;
}

</script>

<input type ="button" value ="AJAX" onclick="showUser(this.value)"/>

<div id="txtHint"></b></div>

</body>
</html>
