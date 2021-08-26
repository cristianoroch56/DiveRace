import React, { useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import { updateForm } from "../../store/actions/StepWizardAction";
import ReactModal from "react-modal";
import Slider from "react-slick";

const PopupCabin2 = (props) => {

    const dispatch = useDispatch();

    const redux_state = useSelector((state) => state.StepWizardReducer);
    const pax_state = useSelector((state) => state.StepWizardReducer.pax);
    const temp_selected_cabins = useSelector((state) => state.StepWizardReducer.temp_selected_cabins);

    const [paxData, setPaxData] = useState({ ...props.paxState });
    const [seatType, setSeatType] = useState(props.paxState.selected);

    const [bow, setBow] = useState('');
    const [beds, setBeds] = useState('');
    const [bathrooms, setBathrooms] = useState('');
    const [gallaryImages, setGallaryImages] = useState([]);

    const customStyles = {
        content: {
            top: "50%",
            left: "50%",
            right: "auto",
            bottom: "auto",
            transform: "translate(-50%, -50%)",
            maxWidth: "480px",
            borderRadius: '10px',
            borderWidth: '0px',
        },
    };

    /* choose Cabin */
    const chooseSeat = (e) => {
        let selectEvent = e.currentTarget.getAttribute("data-value");
        setSeatType(selectEvent);
    };

    //update seat
    const updateSeat = (e) => {
        e.preventDefault();
        
        /* set cabin id and seat type to that person */
        const PERSON_ID = paxData.pax_id;
        const CABIN_ID = paxData.id;
        const CABIN_TYPE = seatType; //solo or 2pax
        const SEAT = 1;

        let pax_array = [...pax_state];
        pax_array[paxData.pax_id].cabin_id = paxData.id;
        pax_array[paxData.pax_id].cabin_type = seatType;
        pax_array[paxData.pax_id].cabin_title = paxData.title;

        let user_cabin_price = seatType == 'solo' ? Number(paxData.price)/2 : paxData.price;
        pax_array[paxData.pax_id].cabin_price = user_cabin_price;

        const stateType = "pax";
        dispatch(updateForm(stateType, pax_array));
 
        /* -------------------------------------logs in temp selected array----------------------- */

        //check person cabin already exists in tem array
        const temp_array  = [...temp_selected_cabins];
        let filter_temp_array = temp_array.filter(temp => temp.person_id != paxData.pax_id);
        let updated_temp_array = [...filter_temp_array, {cabin_id:CABIN_ID, person_id:PERSON_ID, cabin_type:CABIN_TYPE,seat: SEAT}]

        //add cabin in temp selected array
        dispatch(updateForm('temp_selected_cabins', updated_temp_array));
        props.closePopup();
    };

    /* Slider setting */
    const settings = {
        dots: false,
        infinite: true,
        speed: 500,
        slidesToShow: 1,
        slidesToScroll: 1,
        nextArrow: <SampleNextArrow />,
        prevArrow: <SamplePrevArrow />,
    };

    function SampleNextArrow(props) {
        const { className, style, onClick } = props;
        return (
            <div
                className={className}
                style={{ ...style, backgroundImage: `url(${process.env.PUBLIC_URL + "/assets/icon-arrow-right@2x.png"})` }}
                onClick={onClick}
            />
        );
    }

    function SamplePrevArrow(props) {
        const { className, style, onClick } = props;
        return (
            <div
                className={className}
                style={{ ...style, backgroundImage: `url(${process.env.PUBLIC_URL + "/assets/icon-arrow-left@2x.png"})` }}
                onClick={onClick}
            />
        );
    }

    React.useEffect(() => {
     
        //-----------------Get cabin images  Start
        let CABIN_OBJ = redux_state.cabins_list.find((c) => c.id === paxData.id);
        setBow(CABIN_OBJ.bow);
        setBeds(CABIN_OBJ.beds);
        setBathrooms(CABIN_OBJ.bathrooms);
        setGallaryImages(CABIN_OBJ.gallery_image);
        //-----------------Get cabin images  End

        ReactModal.setAppElement("*"); // suppresses modal-related test warnings.
    }, []);

    React.useEffect(() => {

    }, []);


    return (
        <ReactModal
            isOpen={props.showPopupState}
            style={customStyles}
            contentLabel="AddPax Modal"
            ariaHideApp={false}
            onRequestClose={props.closePopup}
        >
            <button onClick={props.closePopup} className="custom-popup-close">X</button>
            <div className="row row-cabin-slider-wrapper">
                <div className="col-md-12">
                    {/* Slider */}
                    <div className="cabin-slick-slider">
                        <Slider {...settings}>
                            {gallaryImages.length > 0 &&
                                gallaryImages.map((glly_obj, i) => {
                                    return (
                                        <div key={i}>
                                            <img src={glly_obj.url} alt={glly_obj.filename} className="gallary_img" />
                                        </div>
                                    )
                                })}
                        </Slider>
                    </div>
                    {/* Slider */}

                    <div className="cabin-title">
                        <h4>{paxData.title}
                            <span>({bow} side bow)</span>
                        </h4>
                    </div>
                    <div className="cabin-amenities">
                        <div className="cabin-beds">
                            <div className="icon-bedroom">
                                <div className="cab-icon">
                                    <img src={process.env.PUBLIC_URL + "/assets/icon-bedroom@2x.png"} alt="Bedoom Icon" />
                                </div>
                                {beds}
                            </div>
                        </div>
                        <div className="cabin-baths">
                            <div className="icon-bathroom">
                                <div className="cab-icon">
                                    <img src={process.env.PUBLIC_URL + "/assets/icon-bathroom@2x.png"} alt="Bathroom Icon" />
                                </div>
                                {bathrooms}
                            </div>
                        </div>
                    </div>
                    
                </div>
             
            </div>
           
        </ReactModal>
    );
};

export default PopupCabin2;
