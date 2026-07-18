<?php
// test_courbe.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Test Dashboard</title>
    <style>
        .chart-box {
            width: 800px;
            height: 400px;
            margin: 50px auto;
            border: 1px solid #ccc;
            padding: 20px;
        }
        canvas {
            border: 1px dashed #999;
        }
        .buttons {
            text-align: center;
            margin: 20px;
        }
        button {
            padding: 10px 20px;
            margin: 5px;
            cursor: pointer;
        }
        button.active {
            background: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <h1 style="text-align:center;">Test Dashboard Chart</h1>
    
    <div class="buttons">
        <button onclick="switchType('line')" id="btn-line">Courbe</button>
        <button onclick="switchType('bar')" id="btn-bar">Bâtonner</button>
        <button onclick="switchType('pie')" id="btn-pie">Circulaire</button>
    </div>
    
    <div class="chart-box">
        <canvas id="financeChart" width="800" height="400"></canvas>
    </div>

    <!-- Charger Chart.js -->
    <script src="assets/js/chart.umd.min.js"></script>
    
    <script>
        console.log('Script démarré');
        
        let currentChart = null;
        
        function createChart(type) {
            console.log('Création graphique type:', type);
            
            const canvas = document.getElementById('financeChart');
            console.log('Canvas trouvé:', !!canvas);
            
            if (!canvas) {
                alert('Canvas non trouvé !');
                return;
            }
            
            const ctx = canvas.getContext('2d');
            
            // Détruire l'ancien graphique
            if (currentChart) {
                currentChart.destroy();
            }
            
            // Données
            const data = {
                labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin'],
                datasets: [
                    {
                        label: 'Gains',
                        data: [5000, 7500, 8000, 9500, 11000, 12500],
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2
                    },
                    {
                        label: 'Pertes',
                        data: [1000, 1500, 2000, 1800, 2200, 2500],
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2
                    }
                ]
            };
            
            // Pour camembert
            if (type === 'pie') {
                data.datasets = [{
                    data: [53500, 11000, 42500],
                    backgroundColor: ['#4CAF50', '#FF5722', '#2196F3'],
                    label: 'Répartition'
                }];
                data.labels = ['Gains', 'Pertes', 'Solde'];
            }
            
            const config = {
                type: type,
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: type === 'pie' ? {} : {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            };
            
            try {
                currentChart = new Chart(ctx, config);
                console.log('Graphique créé avec succès !');
            } catch (error) {
                console.error('Erreur création graphique:', error);
                alert('Erreur: ' + error.message);
            }
        }
        
        function switchType(type) {
            console.log('Switch to:', type);
            
            // Mettre à jour les boutons
            document.querySelectorAll('button').forEach(btn => {
                btn.classList.remove('active');
            });
            document.getElementById('btn-' + type).classList.add('active');
            
            createChart(type);
        }
        
        // Initialiser avec courbe
        window.onload = function() {
            console.log('Page chargée');
            document.getElementById('btn-line').classList.add('active');
            createChart('line');
        };
        
        console.log('Script terminé');
    </script>
</body>
</html>