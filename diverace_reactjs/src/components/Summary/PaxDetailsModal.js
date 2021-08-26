import React, { useEffect } from 'react';
import ReactModal from 'react-modal';
import { useSelector } from "react-redux";

const PaxDetailsModal = (props) => {

    const [details, setDetails] = React.useState([]);

    const customStyles = {
        content: {
            top: "50%",
            left: "50%",
            right: "auto",
            bottom: "auto",
            transform: "translate(-50%, -50%)",
            width: "950px",
            maxWidth: "100%",
            borderRadius: '10px',
            borderWidth: '0px',
        }
    };

    useEffect(() => {

        //setDetails([...props.person]);
        ReactModal.setAppElement('*'); // suppresses modal-related test warnings.
    }, [])



    const Accordion = ({ title, children }) => {
        const [isOpen, setOpen] = React.useState(false);
        return (
            <>
                <div className="accordion-wrapper">

                    <div
                        className={`accordion-title ${isOpen ? "open" : ""}` }
                        onClick={() => setOpen(!isOpen)}
                    >
                        {title}
                    </div>
                    <div className={`accordion-item ${!isOpen ? "collapsed" : ""}`}>
                        <div className="accordion-content row">
                            <div className="col-md-5">
                                <ul>
                                    <li><b>Full Name:</b> Sen warran</li>
                                    <li><b>Email:</b> sen@gmail.com</li>
                                    <li><b>Phone number:</b> 1212190474</li>
                                    <li><b>Gender:</b> Male</li>
                                </ul>
                            </div>
                            <div className="col-md-7">
                                <ul>
                                    <li><b>Cabin:</b> <span>Ocean View Double M2</span></li>
                                    <li><b>Course:</b> <span>Padi divemaster course (DM) (S$800)</span></li>
                                    <li><b>Equipment :</b> <span>Dive Torch (S$10), Wetsuit (XS) (S$10), Dive Computer (S$15)</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </>
        )
    }

    return (
        <ReactModal
            isOpen={props.modalShow}
            style={customStyles}
            contentLabel="Pax Modal"
            onRequestClose={props.closePopup}
            ariaHideApp={false}
        >
            <button onClick={props.closePopup} className="custom-popup-close">X</button>
            {details.length !== 0 && <h4 className="popup-heading heading">Cabin And Person Details</h4>}

            <div className="pax-details-modal m-5">
                <h4 className="popup-heading heading">Person Details</h4>
                <div className="wrapper">
                    <Accordion title="Person 1">
                        Sunlight reaches Earth's atmosphere and is scattered in all directions by
                        all the gases and particles in the air. Blue light is scattered more than
                        the other colors because it travels as shorter, smaller waves. This is why
                        we see a blue sky most of the time.
                    </Accordion>
                    <Accordion title="Person 2">
                        It's really hot inside Jupiter! No one knows exactly how hot, but
                        scientists think it could be about 43,000°F (24,000°C) near Jupiter's
                        center, or core.
                    </Accordion>
                    <Accordion title="Person 3">
                        A black hole is an area of such immense gravity that nothing -- not even
                        light -- can escape from it.
                    </Accordion>
                </div>

            </div>
        </ReactModal>
    );
}

export default PaxDetailsModal;