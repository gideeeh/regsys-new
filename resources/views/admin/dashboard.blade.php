<x-app-layout>
    <div>
        <div>
            <h3>Classes</h3>
        </div>
        <table>
            
        </table>
    </div>
    <div class="w-4/12">
        <canvas id="programChart"></canvas>
        <canvas id="trendChart"></canvas>
    </div>
</x-app-layout>
<script>
    // Enrollment by Program
    var programCtx = document.getElementById('programChart').getContext('2d');
    var programChart = new Chart(programCtx, {
        type: 'bar', // Bar chart for categorical comparison
        data: {
            labels: @json($programData['labels']),
            datasets: [{
                label: 'Enrollments by Program',
                data: @json($programData['data']),
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    var trendCtx = document.getElementById('trendChart').getContext('2d');
    var trendChart = new Chart(trendCtx, {
        type: 'line', // Choose line for trends over time
        data: {
            labels: @json($trendData['labels']),
            datasets: [{
                label: 'Enrollment Trends',
                data: @json($trendData['data']),
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
