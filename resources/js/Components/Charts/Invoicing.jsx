import React from "react";
import { Line } from "react-chartjs-2";

function Invoicing({chartData}) {
    return (
        <div className="invoicing-chart">
            {/* <h2 style={{ textAlign: "center" }}>Line Chart</h2> */}
            <Line
                data={chartData}
                options={{
                    plugins: {
                        title: {
                            display: true,
                            text: "Faturamento deste ano até o momento"
                        },
                        legend: {
                            display: false
                        }
                    }
                }}
            />
        </div>
    )
}

export default Invoicing;