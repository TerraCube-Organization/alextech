$(document).ready(function(){$("#q").on("input",function(){var a=$(this).val();$.ajax({url:"index.php",data:{q:a},success:function(a){$("#searchResults").php(a)}})})});
