import React from 'react';

const ReportForm = ({handleChange, handleSubmit, formState}) => {
    return (
        <form onSubmit={handleSubmit}>
            <div>
                <label>Start Date:</label>
                <input 
                    type="date" 
                    name="startDate" 
                    value={formState.startDate}
                    className="form-control"
                    onChange={handleChange}></input>
            </div>
            <div>
                <label>End Date: </label>
                <input 
                    type="date" 
                    name="endDate" 
                    value={formState.endDate}
                    className="form-control"
                    onChange={handleChange}></input>
            </div>
            <div>
                <button type="submit">Submit</button>
            </div>
        </form>
    );
}

export default ReportForm;