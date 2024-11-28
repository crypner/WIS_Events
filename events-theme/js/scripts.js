document.addEventListener('DOMContentLoaded', function() {
	  const navbar = document.getElementById('navbarContent');
	  const toggler = document.querySelector('.navbar-toggler');

	  navbar.addEventListener('hide.bs.collapse', function() {
			toggler.classList.remove('open');
	  });

	  navbar.addEventListener('show.bs.collapse', function() {
			toggler.classList.add('open');
	  });
});