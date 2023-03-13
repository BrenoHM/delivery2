import ClientScreen from '@/Layouts/ClientScreen';
import { Head } from '@inertiajs/react';
import Invoicing from '@/Components/Charts/Invoicing';
import { useState } from 'react';
import { Data } from "@/helper";
import Chart from "chart.js/auto";
//import { CategoryScale } from "chart.js";

//Chart.register();

export default function Dashboard(props) {
    
    const [chartData, setChartData] = useState({
        labels: props.orders.map((data) => data.month),
        datasets: [
          {
            label: "Ganho R$ ",
            data: props.orders.map((data) => data.value),
            // backgroundColor: [
            //   "rgba(75,192,192,1)",
            //   "&quot;#ecf0f1",
            //   "#50AF95",
            //   "#f3ba2f",
            //   "#2a71d0"
            // ],
            // borderColor: "black",
            // borderWidth: 2,
          }
        ]
    })

    return (
        <ClientScreen {...props}>
            <Head title="Dashboard Cliente" />

            <h1>Dashboard do cliente</h1>
            <div className="grid grid-cols-2">
                <div>
                    <Invoicing chartData={chartData} />
                </div>
            </div>
        </ClientScreen>
    );
}