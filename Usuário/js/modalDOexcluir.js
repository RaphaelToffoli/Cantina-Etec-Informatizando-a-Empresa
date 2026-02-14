const modal = document.getElementById('modalExclusao');
    const btn = document.getElementById('abrirModalExclusao');
    const span = document.getElementsByClassName("close")[0];

    
    btn.onclick = function() {
      modal.style.display = "block";
    }

    
    function fecharModalExclusao() {
      modal.style.display = "none";
    }

    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }