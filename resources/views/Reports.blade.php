<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports Page</title>
    <style>
  #overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
        }
        #popup {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            color:rgb(253, 246, 246);
            border: 1px solid gray;
            background-color: #ff6347;
            margin-bottom: 40px; 
            outline:3px solid white;
            
        }
        .bn{
            margin: 5px;
            padding: 10px;
            font-size: 16px;
            border-radius: 20px;
            font-weight: bold;
        }
        .bn:hover{
            background-color: black;
            color: rgb(251, 244, 244);
            cursor: pointer;
        }
        #bnn{
            background-color: rgb(18, 17, 17);
            color: rgb(253, 246, 246);
            border-radius: 15px;
            margin-top: 25px;
            width: 200px;
            height:50px
            
        }
        #bnn:hover{
            font-size: larger;
        }
        @media screen and (min-width:480px) {
            .bn{
                width: 100%;
            }
            #popup{
                margin-top:15vh;
            }
        }
    </style>
</head>
<body>

    <div id="overlay">
        <div id="popup">
            <button class="bn" onclick="Student()">Students Report</button>
            <button class="bn" onclick="Teacher()">Teachers Report</button>
            <button class="bn" onclick="Course()">Courses Report</button>
            <button class="bn" onclick="Department()">Departments Report</button>
            <br>
            <button id="bnn" onclick="closePopup()">Close</button>
        </div>
    </div>

    <script>
        function Student(){
            window.location.href="Reports/Students-Reports.php";
        }
        function Teacher(){
            window.location.href="Reports/Teachers-Reports.php";
        }
        function Course(){
            window.location.href="Reports/Course-Reports.php";
        }
        function Department(){
            window.location.href="Reports/Department-Reports.php";
        }
        function showPopup() {
            document.getElementById("overlay").style.display = "flex";
        }
        function closePopup() {
            document.getElementById("overlay").style.display = "none";
        }
    </script>
</body>
</html>