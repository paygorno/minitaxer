import React from 'react';

const ForceExchangeForm = ({handleChange, formState}) => {
    return [
        <div key="amount">
            <label>Amount:</label>
            <input 
                type="text" 
                name="amount" 
                value={formState.amount}
                className="form-control"
                onChange={handleChange}></input>
        </div>,
        <div key="currencyCode">
            <label>Currency:</label>
            <select
                name="currencyCode" 
                value={formState.currencyCode}
                className="form-control"
                onChange={handleChange}>
                <option value="">Select</option>
                <option value="USD">USD</option>
            </select>
        </div>,
        <div key="date">
            <label>Date</label>
            <input 
                type="date" 
                name="date" 
                value={formState.date}
                className="form-control"
                onChange={handleChange}></input>
        </div>,
        <div key="rate">
            <label>Rate</label>
            <input 
                type="text" 
                name="rate" 
                value={formState.rate}
                className="form-control"
                onChange={handleChange}></input>
        </div>
    ];;
}

export default ForceExchangeForm;