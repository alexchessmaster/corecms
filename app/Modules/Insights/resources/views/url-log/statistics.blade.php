@extends('shared::partials.app')
@section('content-card-title', 'Upload')
@section('content-card-body')

    <div>
        <canvas id="visit_chart"></canvas>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('visit_chart');
        let labelString = '{{ $labelString }}';
        let labels = labelString.split(',');
        let dataString = '{{ $dataString }}';
        let data = dataString.split(',');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: '# of Votes',
                    data: data,
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

@endsection
