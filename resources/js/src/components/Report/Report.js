import React from 'react';

const Report = ({reportData}) => {
    return (
        <div>
            <h3>
                Report from {reportData.startDate} to {reportData.endDate}
            </h3>
            <div>
                <div>
                    Income:
                </div>
                <div>
                    {Number(reportData.gain).toFixed(2)}
                </div>
            </div>
            <div>
                <div>
                    Tax:
                </div>
                <div>
                    {Number(reportData.tax).toFixed(2)}
                </div>
            </div>
        </div>
    );
}

export default Report;