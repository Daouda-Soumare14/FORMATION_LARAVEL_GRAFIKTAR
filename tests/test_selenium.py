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
    url = "http://127.0.0.1:8000/login"
    print(f"[ℹ️] Chargement de la page : {url}")
    
    driver.get(url)

    try:
        WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.NAME, "email")))
        print("[✅] Page de connexion chargée avec succès.")
    except Exception as e:
        print(f"[❌] Erreur : {str(e)}")
        raise AssertionError("La page de connexion ne s'est pas chargée correctement.")

def test_login(driver):
    """Test d'authentification avec des identifiants valides."""
    url = "http://127.0.0.1:8000/login"
    driver.get(url)
    
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.NAME, "email")))

    # Remplir le formulaire
    driver.find_element(By.NAME, "email").send_keys("daoudasoum14@gmail.com")
    driver.find_element(By.NAME, "password").send_keys("soumare")
    print("[✅] Formulaire rempli.")

    # Cliquer sur le bouton de connexion
    driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
    print("[✅] Bouton de connexion cliqué.")

    # Vérifier la redirection après connexion
    WebDriverWait(driver, 10).until(lambda d: d.current_url != url)
    print(f"[ℹ️] URL après connexion : {driver.current_url}")

    # Vérifier si la connexion a réussi
    try:
        WebDriverWait(driver, 5).until(EC.presence_of_element_located((By.CSS_SELECTOR, ".nav-link.logout")))
        print("[✅] Connexion réussie !")
    except:
        try:
            error_message = driver.find_element(By.CSS_SELECTOR, ".text-danger").text.strip()
        except:
            error_message = "[⚠️] Aucun message d'erreur détecté, mais la connexion a échoué."

        raise AssertionError(f"[❌] Connexion échouée : {error_message}")

    # Vérifier le titre de la page après connexion
    print(f"[✅] Titre après connexion : {driver.title}")
