from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

# Configurer les options du navigateur
options = webdriver.ChromeOptions()
options.add_argument("--headless")  
options.add_argument("user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36")

# Connexion à Selenium dans Docker
driver = webdriver.Remote(
    command_executor="http://localhost:4444/wd/hub",
    options=options
)

try:
    print("[ℹ️] Chargement de la page de connexion...")
    driver.get("http://host.docker.internal:8000/login")

    # Vérification si la page est bien chargée
    WebDriverWait(driver, 5).until(EC.presence_of_element_located((By.NAME, "email")))
    print("[✅] Page de connexion détectée.")

    # Remplir le formulaire de connexion
    driver.find_element(By.NAME, "email").send_keys("daoudasoum14@gmail.com")
    driver.find_element(By.NAME, "password").send_keys("soumare")
    print("[✅] Formulaire rempli.")

    # Cliquer sur le bouton de connexion
    driver.find_element(By.CSS_SELECTOR, "button.btn.btn-primary").click()
    print("[✅] Bouton de connexion cliqué.")

    # Attendre que la page se charge après la soumission
    WebDriverWait(driver, 5).until(EC.presence_of_element_located((By.TAG_NAME, "body")))

    # Vérifier si on est toujours sur la page de connexion
    current_url = driver.current_url
    print(f"[ℹ️] URL après connexion : {current_url}")

    if current_url == "http://host.docker.internal:8000/login":
        # Rechercher les erreurs affichées en text-danger
        error_elements = driver.find_elements(By.CSS_SELECTOR, ".text-danger")
        error_messages = [error.text.strip() for error in error_elements if error.text.strip()]

        if error_messages:
            print("[❌] Erreurs détectées :")
            for error in error_messages:
                print(f"   - {error}")
        else:
            print("[⚠️] Toujours sur /login, mais aucun message d'erreur visible.")
    
    else:
        print("[✅] Connexion réussie !")

    # Afficher le titre de la page après connexion
    print("[ℹ️] Titre après connexion :", driver.title if driver.title else "[⚠️] Aucun titre détecté.")

except Exception as e:
    print(f"[❌] Erreur inattendue : {e}")

finally:
    print("[ℹ️] Fermeture de Selenium.")
    driver.quit()

