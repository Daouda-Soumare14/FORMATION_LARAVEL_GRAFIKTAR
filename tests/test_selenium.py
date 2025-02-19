import sys
import time
import pytest
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

@pytest.fixture(scope="module")
def driver():
    """Initialisation et fermeture automatique de Selenium WebDriver."""
    options = webdriver.ChromeOptions()
    options.add_argument("--headless")  
    options.add_argument("user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36")

    driver = webdriver.Remote(
        command_executor="http://localhost:4444/wd/hub",
        options=options
    )
    yield driver
    driver.quit()

def test_login_page_load(driver):
    """Test de chargement de la page de connexion."""
    print("[ℹ️] Chargement de la page de connexion...")
    driver.get("http://host.docker.internal:8000/login")

    try:
        WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.NAME, "email")))
        print("[✅] Page de connexion détectée.")
    except:
        raise AssertionError("[❌] La page de connexion ne s'est pas chargée correctement.")

def test_login(driver):
    """Test d'authentification avec des identifiants valides."""
    driver.get("http://host.docker.internal:8000/login")
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.NAME, "email")))

    # Remplir le formulaire de connexion
    driver.find_element(By.NAME, "email").send_keys("daoudasoum14@gmail.com")
    driver.find_element(By.NAME, "password").send_keys("soumare")
    print("[✅] Formulaire rempli.")

    # Cliquer sur le bouton de connexion
    driver.find_element(By.CSS_SELECTOR, "button.btn.btn-primary").click()
    print("[✅] Bouton de connexion cliqué.")

    # Attendre la redirection
    WebDriverWait(driver, 5).until(EC.url_changes("http://host.docker.internal:8000/login"))
    print(f"[ℹ️] URL après connexion : {driver.current_url}")

    # Vérifier si la connexion a réussi en détectant un élément spécifique
    try:
        WebDriverWait(driver, 5).until(EC.presence_of_element_located((By.CSS_SELECTOR, ".nav-link.logout")))
        print("[✅] Connexion réussie !")
    except:
        error_message = ""
        try:
            error_element = driver.find_element(By.CSS_SELECTOR, ".text-danger")
            error_message = error_element.text.strip()
        except:
            error_message = "[⚠️] Aucun message d'erreur visible, mais la connexion semble échouer."

        raise AssertionError(f"[❌] La connexion a échoué : {error_message}")

    # Afficher le titre de la page après connexion
    print("[✅] Titre après connexion:", driver.title)
