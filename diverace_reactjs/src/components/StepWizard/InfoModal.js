import React from "react";
import ReactModal from "react-modal";

const InfoModal = (props) => {

    const customStyles = {
        content: {
            top: "50%",
            left: "50%",
            right: "auto",
            bottom: "auto",
            transform: "translate(-50%, -50%)",
            width: "100%",
            maxWidth: "800px",
            borderRadius: '10px',
            borderWidth: '0px',
        },
    };

    return (
        <ReactModal
            isOpen={props.isOpen}
            style={customStyles}
            contentLabel="Sizeguide Modal"
            ariaHideApp={false}
            onRequestClose={props.closePopup}
        >
            <button onClick={props.closePopup} className="custom-popup-close">X</button>
            <h4 className="text-center mt-5 ">Booking Made - Please Read Me Carefully!</h4>
            <div className="row" style={{paddingLeft:50, paddingRight:50}}>



                <p><strong>Awesome!</strong> You have just made the booking and 1st payment for your trip! We are definitely looking forward to having you on board so that we can pamper you.Before we get to the fun part of diving, there are some administrative matters that we will need you to handle. From now until a week before the trip departure date, feel free to submit the following information to us via your booking reference ID: <span className="text-primary">20210104_12345</span></p>

                <ul>
                    <li>Immigration details</li>
                    <li>Diving certification details</li>
                    <li>Emergency details</li>
                    <li>Emergency details</li>
                    <li>Rental Equipment (if any)</li>
                    <li>Scuba course (if any)</li>
                </ul>


                <p>Do remember that you have 24hrs to change your mind. If you decide to cancel, we will refund you your funds with no questions asked!</p>


                <h6> Payment Timeline (Conditional based on payment)</h6>

                <ul>
                    <li>10% - Booking made (24hrs to change mind)</li>
                    <li> 40% - 14 days later</li>
                    <li>50% - 45 days before the trip departure date</li>

                </ul>

                <p> If there are any balance payment or information required that is due, a reminder notification will be sent to you via email. The invoice must be fully paid 45 days before the trip departure date to ensure that your booking is confirmed. If you have any questions, please take a look at our payment related FAQs. (<a href="https://diverace.chillybin.biz/docs/" title="FAQs">FAQs</a>)
                We understand that there are times when the unforeseeable may happen. Thus, we strongly advise you to get a travel insurance from this very moment so as to protect yourself for any form of cancellation. DiveAssure with liveaboard rider is our recommended choice of insurer as it would cover travel and diving related matters.
                If you have any queries, please feel free to contact us. We will get back to you latest by 3 working days.</p>

            </div>
        </ReactModal >
    );
};

export default InfoModal;
