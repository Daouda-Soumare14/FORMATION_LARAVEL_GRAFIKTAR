from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

# Configuration WebDriver avec Chrome headless
options = webdriver.ChromeOptions()
options.add_argument("--headless")
options.add_argument("user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36")

# Vérification de l'environnement : Local ou GitHub Actions
SELENIUM_URL = "http://localhost:4444/wd/hub"

driver = webdriver.Remote(
    command_executor=SELENIUM_URL,
    options=options
)

try:
    print("[ℹ️] Chargement de la page de connexion...")
    driver.get("http://localhost:8000/login")  # Changer host.docker.internal → localhost

    # Vérifier si le champ email est présent
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.NAME, "email")))
    print("[✅] Page de connexion détectée.")

    # Remplir le formulaire
    driver.find_element(By.NAME, "email").send_keys("daoudasoum14@gmail.com")
    driver.find_element(By.NAME, "password").send_keys("soumare")
    print("[✅] Formulaire rempli.")

    # Cliquer sur le bouton de connexion
    driver.find_element(By.CSS_SELECTOR, "button.btn.btn-primary").click()
    print("[✅] Bouton de connexion cliqué.")

    # Attendre la redirection
    WebDriverWait(driver, 10).until(EC.url_changes("http://localhost:8000/login"))
    print(f"[✅] Connexion réussie ! URL actuelle : {driver.current_url}")

    # Afficher le titre de la page après connexion
    print("[✅] Titre après connexion:", driver.title)

except Exception as e:
    print(f"[❌] Erreur détectée : {str(e)}")

finally:
    driver.quit()
