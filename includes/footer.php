</main>
<script>
  var tombolTema = document.getElementById('toggleTema');
  if (tombolTema) {
    tombolTema.addEventListener('click', function () {
      var root = document.documentElement;
      var temaSekarang = root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
      var temaBaru = temaSekarang === 'dark' ? 'light' : 'dark';
      root.setAttribute('data-theme', temaBaru);
      localStorage.setItem('tema', temaBaru);
    });
  }
</script>
</body>
</html>
