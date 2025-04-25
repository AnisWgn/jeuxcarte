    </div>
    <footer class="mt-5 py-5" style="background: linear-gradient(135deg, #f0f4f9 0%, #d8e3f3 100%); border-top: 1px solid rgba(0,0,0,0.05);">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="mb-4" style="font-weight: 600; color: #2d3748; position: relative; display: inline-block;">
                        <i class="fas fa-globe-americas me-2" style="color: #4f46e5;"></i>PokeGeo
                        <span style="position: absolute; bottom: -5px; left: 0; width: 40px; height: 3px; background: linear-gradient(90deg, #4f46e5, #7c3aed); border-radius: 3px;"></span>
                    </h5>
                    <p style="color: #6b7280; line-height: 1.6;">Collectionnez des cartes virtuelles uniques représentant différents pays et régions du monde.</p>
                    <div class="d-flex mt-4">
                        <a href="#" class="me-3" style="width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; text-decoration: none;">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="me-3" style="width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; text-decoration: none;">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="me-3" style="width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; text-decoration: none;">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-md-2 mb-4 mb-md-0">
                    <h6 style="font-weight: 600; color: #2d3748; margin-bottom: 1.2rem;">Navigation</h6>
                    <ul class="list-unstyled" style="line-height: 2;">
                        <li><a href="/portfolio/project/Jeux_carte/index.php" style="color: #6b7280; text-decoration: none; transition: color 0.2s ease;">Accueil</a></li>
                        <li><a href="/portfolio/project/Jeux_carte/cards.php" style="color: #6b7280; text-decoration: none; transition: color 0.2s ease;">Cartes</a></li>
                        <li><a href="/portfolio/project/Jeux_carte/shop.php" style="color: #6b7280; text-decoration: none; transition: color 0.2s ease;">Boutique</a></li>
                        <li><a href="/portfolio/project/Jeux_carte/collection.php" style="color: #6b7280; text-decoration: none; transition: color 0.2s ease;">Collection</a></li>
                    </ul>
                </div>
                
                <div class="col-md-2 mb-4 mb-md-0">
                    <h6 style="font-weight: 600; color: #2d3748; margin-bottom: 1.2rem;">Compte</h6>
                    <ul class="list-unstyled" style="line-height: 2;">
                        <li><a href="/portfolio/project/Jeux_carte/login.php" style="color: #6b7280; text-decoration: none; transition: color 0.2s ease;">Connexion</a></li>
                        <li><a href="/portfolio/project/Jeux_carte/register.php" style="color: #6b7280; text-decoration: none; transition: color 0.2s ease;">Inscription</a></li>
                        <li><a href="/portfolio/project/Jeux_carte/account.php" style="color: #6b7280; text-decoration: none; transition: color 0.2s ease;">Mon Compte</a></li>
                    </ul>
                </div>
                
                <div class="col-md-4">
                    <h6 style="font-weight: 600; color: #2d3748; margin-bottom: 1.2rem;">À propos</h6>
                    <p style="color: #6b7280; line-height: 1.6; margin-bottom: 1rem;">PokeGeo est un jeu de collection de cartes virtuelles géographiques. Explorez le monde à travers des cartes uniques et construisez votre collection.</p>
                    <p style="color: #6b7280; font-size: 0.9rem;">
                        <i class="fas fa-envelope me-2" style="color: #4f46e5;"></i>contact@pokegeo.com
                    </p>
                </div>
            </div>
            
            <hr style="margin: 2rem 0; opacity: 0.1;">
            
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <p class="mb-0" style="color: #6b7280; font-size: 0.9rem;">&copy; <?php echo date('Y'); ?> PokeGeo. Tous droits réservés.</p>
                </div>
                <div class="col-md-6">
                    <ul class="list-inline text-md-end mb-0">
                        <li class="list-inline-item">
                            <a href="#" style="color: #6b7280; text-decoration: none; font-size: 0.9rem; transition: color 0.2s ease;">Conditions d'utilisation</a>
                        </li>
                        <li class="list-inline-item ms-3">
                            <a href="#" style="color: #6b7280; text-decoration: none; font-size: 0.9rem; transition: color 0.2s ease;">Politique de confidentialité</a>
                        </li>
                        <li class="list-inline-item ms-3">
                            <a href="#" style="color: #6b7280; text-decoration: none; font-size: 0.9rem; transition: color 0.2s ease;">Aide</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script pour ajouter la classe 'active' au lien de navigation actuel
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath || currentPath.includes(link.getAttribute('href'))) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html> 