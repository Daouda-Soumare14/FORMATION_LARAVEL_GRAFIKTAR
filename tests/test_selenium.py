import sys
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time

# Configurer les options du navigateur
options = webdriver.ChromeOptions()
options.add_argument("--headless")  
options.add_argument("user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36")

# Connexion à Selenium dans Docker
driver = webdriver.Remote(
    command_executor="http://localhost:4444/wd/hub",
    options=options
)

print("[ℹ️] Chargement de la page de connexion...")
driver.get("http://127.0.0.1:8000/login")
time.sleep(2)  # Attendre un peu pour éviter des problèmes de chargement

# Vérifier si la page de connexion est bien chargée
if "Se connecter" in driver.page_source:
    print("[✅] Page de connexion détectée.")
else:
    print("[❌] La page de connexion ne s'est pas chargée correctement.")
    sys.exit(1)  # Échec du test si la page ne charge pas

# Remplir le formulaire de connexion
driver.find_element(By.NAME, "email").send_keys("daoudasoum14@gmail.com")
driver.find_element(By.NAME, "password").send_keys("soumare")
print("[✅] Formulaire rempli.")

# Cliquer sur le bouton de connexion
driver.find_element(By.CSS_SELECTOR, "button.btn.btn-primary").click()
print("[✅] Bouton de connexion cliqué.")

# Attendre la redirection après connexion
time.sleep(3)
print(f"[ℹ️] URL après connexion : {driver.current_url}")

# Vérifier si on est toujours sur la page de connexion
if "/login" in driver.current_url:
    print("[⚠️] Toujours sur /login : possible échec d'authentification.")

    # Vérifier si un message d'erreur s'affiche
    try:
        error_element = driver.find_element(By.CSS_SELECTOR, ".text-danger")
        error_message = error_element.text.strip()
        print(f"[❌] Erreur détectée : {error_message}")
        driver.quit()
        sys.exit(1)  # Échouer le test si une erreur est trouvée
    except:
        print("[⚠️] Aucun message d'erreur visible, mais la connexion semble échouer.")
        driver.quit()
        sys.exit(1)  # Échouer le test si l'authentification échoue sans message clair

# Vérifier la présence d'un élément spécifique pour valider la connexion
try:
    WebDriverWait(driver, 5).until(EC.presence_of_element_located((By.CSS_SELECTOR, ".nav-link.logout")))
    print("[✅] Connexion réussie !")
except:
    print("[❌] L'élément de déconnexion n'a pas été trouvé. La connexion a peut-être échoué.")
    sys.exit(1)

# Afficher le titre de la page après connexion
print("[✅] Titre après connexion:", driver.title)

# Fermer Selenium
driver.quit()
