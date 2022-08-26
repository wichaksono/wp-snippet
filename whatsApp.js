<button id="wame">WhatsApp</button>

<script>

let buttonWa = document.getElementById("wame");

buttonWa.addEventListener('click', function() {

	let noWhatsapp = "https://wa.me/6282244274544";
    let message    = "?text=" + encodeURI("*hallo* Bossku\n Aku meh tekon");
    
   	window.open(noWhatsapp + message);
});

</script>
