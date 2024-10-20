<!-- app/Views/partials/carDamageModal.php -->

<!-- Modal for Car Damage Selection -->
<div class="modal fade" id="carDamageModal" tabindex="-1" aria-labelledby="carDamageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="carDamageModalLabel">Select Car Damage</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- SVG Car Graphic -->
                <div id="car-container">
<svg id="car-diagram" width="100%" height="400" viewBox="0 0 500 300" xmlns="http://www.w3.org/2000/svg">
  <!-- Car body -->
  <rect id="hood" x="90" y="80" width="100" height="50" fill="gray" stroke="black" />
  <rect id="roof" x="190" y="50" width="120" height="50" fill="gray" stroke="black" />
  <rect id="trunk" x="310" y="80" width="100" height="50" fill="gray" stroke="black" />

  <!-- Car doors -->
  <rect id="left-door" x="190" y="100" width="60" height="80" fill="gray" stroke="black" />
  <rect id="right-door" x="250" y="100" width="60" height="80" fill="gray" stroke="black" />

  <!-- Car wheels -->
  <circle id="left-front-wheel" cx="150" cy="200" r="30" fill="black" />
  <circle id="right-front-wheel" cx="350" cy="200" r="30" fill="black" />

  <!-- Car windows -->
  <polygon id="left-window" points="190,50 210,50 210,100 190,100" fill="lightblue" stroke="black" />
  <polygon id="right-window" points="290,50 310,50 310,100 290,100" fill="lightblue" stroke="black" />

  <!-- Car mirrors -->
  <rect id="left-mirror" x="180" y="80" width="10" height="20" fill="gray" stroke="black" />
  <rect id="right-mirror" x="320" y="80" width="10" height="20" fill="gray" stroke="black" />

  <!-- Car lights -->
  <circle id="left-headlight" cx="100" cy="100" r="10" fill="yellow" />
  <circle id="right-headlight" cx="400" cy="100" r="10" fill="yellow" />
</svg>

                </div>

                <!-- Popup for Selecting Damage Severity -->
                <div id="damageSeverity" style="display:none;">
                    <h5>Select Damage Severity</h5>
                    <select id="damage-severity" class="form-select">
                        <option value="light">Light</option>
                        <option value="moderate">Moderate</option>
                        <option value="severe">Severe</option>
                    </select>
                    <button class="btn btn-primary mt-2" id="saveDamage">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Car Damage Selection -->
<script>
    // Open the damage severity selection popup
// Open the damage severity selection popup
let selectedPart = null;

document.querySelectorAll('#car-diagram [id]').forEach(function(part) {
    part.addEventListener('click', function() {
        selectedPart = part;
        document.getElementById('damageSeverity').style.display = 'block';
    });
});

// Handle save button click in the damage severity selection
document.getElementById('saveDamage').addEventListener('click', function() {
    const severity = document.getElementById('damage-severity').value;

    // Change the color of the selected part based on severity
    if (severity === 'light') {
        selectedPart.setAttribute('fill', 'yellow');
    } else if (severity === 'moderate') {
        selectedPart.setAttribute('fill', 'orange');
    } else if (severity === 'severe') {
        selectedPart.setAttribute('fill', 'red');
    }

    // Hide the damage severity selection
    document.getElementById('damageSeverity').style.display = 'none';
});

</script>
