import React, { useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import { updateForm } from "../../store/actions/StepWizardAction";
import ReactModal from "react-modal";
import Select from "react-select";
import Slider from "react-slick";

const PopupCabin = (props) => {

  const redux_state = useSelector((state) => state.StepWizardReducer);
  const pax_state = useSelector((state) => state.StepWizardReducer.pax);

  const selected_cabins_state = useSelector((state) => state.StepWizardReducer.cabin_types);

  const dispatch = useDispatch();
  const [paxData, setPaxData] = useState({ ...props.paxState });
  const [valuescabins, setvaluescabins] = useState({
    selected_cabins: selected_cabins_state,
  });

  const [paxvalues, setPaxvalues] = useState({
    pax: pax_state
  });

  const [personOptions, setpPersonOptions] = useState([]);
  const [type, setType] = useState("");
  const [multi, setMulti] = useState(false);

  const [selectedValues, setSelectedValues] = useState([]);
  const [selectedPersons, setSelectedPersons] = useState([]);

  const [bow, setBow] = useState('');
  const [beds, setBeds] = useState('');
  const [bathrooms, setBathrooms] = useState('');  
  const [gallaryImages, setGallaryImages] = useState([]);

  const customSelectStyles = {
    // For the select it self, not the options of the select
    control: (styles, { isDisabled }) => {
      return {
        ...styles,
        backgroundColor: isDisabled && "#white",
        borderColor: isDisabled ? "#d9d9d9" : "#d9d9d9",
      };
    },
    option: (styles, {}) => {
      return {
        ...styles,
        fontSize: 15,
      };
    },
    menuList: (styles, {}) => {
      return {
        ...styles,
        "::-webkit-scrollbar": { width: "0 !important" },
        scrollbarWidth: "none",
        maxHeight: 200,
      };
    },
  };

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

  const handleChange = (options) => {

    if(options && options.length > 2 ) return;
    if (options != null) {
      var labels = Array.isArray(options)  ? options.map((x) => x ? x.label : null) : [options.label];
      setSelectedValues(labels);
    } else {
      setSelectedValues([]);
    }
  };

  /* Update Cabin */
  const update2paxCabin = (e) => {
    e.preventDefault();

    /* check if persons are selected */
    if (selectedValues.length > 0) {

      let CABIN_TYPE = props.type == "solo_spot" ? "solo" : "2pax";

      var vals_cabins = [...valuescabins.selected_cabins];
      /* add selected cabin and persons in cabin state */
      var vals_cabins = [
        ...vals_cabins,
        {
          id: Number(paxData.id),
          title: paxData.title,
          type: CABIN_TYPE,
          seat: "both",
          price: paxData.price,
          person: selectedValues,
        },
      ];
      setvaluescabins({ selected_cabins: vals_cabins });
      dispatch(updateForm("cabin_types", vals_cabins));

      let perosnsArray = [...paxvalues.pax];

      perosnsArray.forEach((element, index) => {
        let findPerson = `Person${index + 1}`;
        if(selectedValues.includes(findPerson)) {
          perosnsArray[index]['is_used'] = true;
        }
      });

      setPaxvalues({ pax: [...perosnsArray]}) 
    }
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
    dispatch(updateForm('pax', paxvalues.pax));
  }, [paxvalues]);

  React.useEffect(() => {

    //-----------------Get cabin images  Start
    let CABIN_OBJ = redux_state.cabins_list.find((c) => c.id === paxData.id);
    setBow(CABIN_OBJ.bow);
    setBeds(CABIN_OBJ.beds);
    setBathrooms(CABIN_OBJ.bathrooms);    
    setGallaryImages(CABIN_OBJ.gallery_image);
    //------------------Get cabin images  End

    let checkType = props.type == "solo_spot" ? "solo" : "2pax";

    //filter array by type
    var filterOptions = props.options.filter((obj) => {
      return obj.type === checkType && obj.is_used === false;
    });

    let selectType = props.type == "solo_spot" ? false : true;
    setMulti(selectType);
    setpPersonOptions(filterOptions);
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
            <div className="cabin-pax-data">
                <h5>Select Pax:</h5>      
                <Select 
                options={personOptions} 
                isMulti={multi} 
                onChange={handleChange} 
                styles={customSelectStyles}
                value={personOptions.filter(obj => selectedValues.includes(obj.value))}
                ></Select>
            </div>                
        </div>
        {/* {selectedValues.map(o => <p>{o.value}</p>)} */}
      </div>
      <div className="row">
        <div className="col-md-12 text-right mt-30">
          {/* <button className="btn btn-sm btn-primary" onClick={props.closePopup}>OK</button> */}
          <button className="btn btn-lg btn-primary btn-popup-pax" onClick={update2paxCabin}>
            Apply
          </button>
        </div>
      </div>
    </ReactModal>
  );
};

export default PopupCabin;
