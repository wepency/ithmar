
 function ToJavaScriptDate(value) {
     var pattern = /Date\(([^)]+)\)/;
         var results = pattern.exec(value);
         var dt = new Date(parseFloat(results[1]));

         return dt.getDate() + "/" +  (dt.getMonth() + 1)+ "/" + dt.getFullYear();
    

            }

