const gekookt = document.getElementById('gekookt');
const addscore = async (receptId) => {
  try {
    const response = await fetch('../recept/addscore.php', {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({ recept_id: receptId })
    });
    const data = await response.json();
    if (data.success) {
      gekookt.innerHTML = '';
      gekookt.innerHTML = '<span class="badge bg-success">Je hebt dit recept al gekookt!</span>';
    } else if (data.error) {
      gekookt.innerHTML += `<span class="badge bg-warning">${data.error}</span>`;
    }
  } catch (error) {
    console.error('Fout bij het toevoegen van de score:', error);
    gekookt.innerHTML = `<span class="badge bg-danger">Er is een fout opgetreden, ${error}. Probeer het later opnieuw.</span>`;

  }
};