import React from 'react';

const Profile = ({profile, toggle}) => {
    return (
        <div>
            <h2>Profile</h2>
            <div>
                <div>
                    Fitst Name:
                </div>
                <div>
                    {profile.firstName}
                </div>
            </div>
            <div>
                <div>
                    Last Name:
                </div>
                <div>
                    {profile.lastName}
                </div>
            </div>
            <div>
                <div>
                    Tax Rate:
                </div>
                <div>
                    {profile.taxRate}
                </div>
            </div>
            <div>
                <div>
                    Force Exchange:
                </div>
                <div>
                    <input 
                        type="checkbox"
                        checked={profile.forceExchange}
                        disabled></input>
                </div>
            </div>
            <div>
                <div>
                    Force Exchange Amount:
                </div>
                <div>
                    {profile.forceExchangeAmount}
                </div>
            </div>
            <div>
                <div>
                    Notification Period:
                </div>
                <div>
                    {profile.notificationPeriod}
                </div>
            </div>
            <div>
                <button onClick={toggle}>
                    Edit
                </button>
            </div>
        </div>
    );
};

export default Profile;