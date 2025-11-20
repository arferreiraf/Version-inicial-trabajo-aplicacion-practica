<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script>
	window.onload = detectarCarga;
		function detectarCarga(){
		document.getElementById("loader").style.display="none";
	}
	document.getElementById("loader").style.display="block";
</script>
<style>
.loader {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url('../images/loader_1.gif') 50% 50% no-repeat rgb(255,255,255);
    opacity: .9;
}
</style>
<div id="loader" class='loader'></div>
