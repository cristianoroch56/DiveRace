import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import ReactModal from "react-modal";
import {
  updateForm,
  updateSummary,
} from "../../store/actions/StepWizardAction";


const PopupCabinType = (props) => {
  const redux_state = useSelector((state) => state.StepWizardReducer);
  const selected_cabins_state = useSelector(
    (state) => state.StepWizardReducer.cabin_types
  );
  const [paxData, setPaxData] = useState({ ...props.paxState });
  const [seatType, setSeatType] = useState("");
  const [total_selected_cabins, setTotal_selected_cabins] = useState(0);
  const dispatch = useDispatch();

  const [valuescabins, setvaluescabins] = useState({
    selected_cabins: selected_cabins_state,
  });


  const customStyles = {
    content: {
      top: "50%",
      left: "50%",
      right: "auto",
      bottom: "auto",
      transform: "translate(-50%, -50%)",
      width: "auto",
      maxHeight: "90vh",
      overflow: "auto",
    },
  };

  const update2paxCabin = (e) => {
    //e.preventDefault();
    let selectEvent = e.currentTarget.getAttribute("data-value");

    var vals_cabins = [...valuescabins.selected_cabins];

    let cabinIndex = vals_cabins.findIndex((x) => x.id === Number(paxData.id));

    const CABIN_TYPE = "2pax";
    if (cabinIndex !== -1) {
      vals_cabins.splice(cabinIndex, 1);
    }

    if (selectEvent === "solo") {
      let remaining_pax = redux_state.passenger - Number(total_selected_cabins);
      if (remaining_pax < 1) {
        props.closePopup();
        props.validatePopUp(true);
        setTimeout(function () {
          props.validatePopUp(false);
        }, 3000);

        return;
      }

      let set_seat_type =
        paxData.seat_type === "empty"
          ? "left"
          : paxData.seat_type === "left" || paxData.seat_type === "right"
          ? "right"
          : "both";
      var vals_cabins = [
        ...vals_cabins,
        {
          id: Number(paxData.id),
          title: paxData.title,
          type: CABIN_TYPE,
          seat: set_seat_type,
          price: paxData.price,
        },
      ];
    } else {
      let remaining_pax = redux_state.passenger - Number(total_selected_cabins);
      if (remaining_pax < 2) {
        props.closePopup();
        props.validatePopUp(true);
        setTimeout(function () {
          props.validatePopUp(false);
        }, 3000);
        return;
      }
      var vals_cabins = [
        ...vals_cabins,
        {
          id: Number(paxData.id),
          title: paxData.title,
          type: CABIN_TYPE,
          seat: "both",
          price: paxData.price,
        },
      ];
    }

    setSeatType(selectEvent);
    setvaluescabins({ selected_cabins: vals_cabins });
    dispatch(updateForm("cabin_types", vals_cabins));
  };

  useEffect(() => {
    setPaxData(props.paxState);
    setvaluescabins({ selected_cabins: selected_cabins_state });
    ReactModal.setAppElement("*"); // suppresses modal-related test warnings.
    let no_of_selected_cabins = 0;
    if (selected_cabins_state.length > 0) {
      for (let i = 0; i < selected_cabins_state.length; i++) {
        no_of_selected_cabins =
          selected_cabins_state[i].type == "solo"
            ? no_of_selected_cabins + 1
            : selected_cabins_state[i].seat === "both"
            ? no_of_selected_cabins + 2
            : no_of_selected_cabins + 1;
      }
    }

    setTotal_selected_cabins(Number(no_of_selected_cabins));
  }, [props.paxState, selected_cabins_state]);



  return (
    <ReactModal
      isOpen={props.showPopupState}
      style={customStyles}
      contentLabel="PopupCabinType Modal"
      ariaHideApp={false}
      //    closeTimeoutMS={2000}
      onRequestClose={props.closePopup}
    >
      <div className="row">
        <div className="col-md-12">

          <h5>Select Cabin Type:</h5>
          {/* <span className="text-danger">Only Solo Cabin left</span> */}
          ​
          <br />
          <div className="form-check-inline radio-btn-fancy">
            <input
              type="radio"
              id="test1"
              name="radio-group"
              className="form-control"
              defaultChecked={seatType === "solo"}
              data-value="solo"
              onClick={update2paxCabin}
            />
            <label htmlFor="test1"> Solo </label>
          </div>
          {/* {paxData.seat_type === "empty" && */}
          <div
            className={`form-check-inline radio-btn-fancy ${
              paxData.seat_type !== "empty" && "inputradio_seatalreadyselected"
            }`}
          >
            <input
              type="radio"
              id="test2"
              name="radio-group"
              defaultChecked={seatType === "2pax"}
              data-value="2pax"
              onClick={
                paxData.seat_type === "empty" ? update2paxCabin : undefined
              }
              disabled={paxData.seat_type !== "empty" && true}
            />
            <label htmlFor="test2">
              2pax <span className="text-danger"></span>{" "}
              {paxData.seat_type !== "empty" && "(Already Booked)"}
            </label>
          </div>
          
          {/* } */}
          {/* <button className="close" aria-label="Close" onClick={props.closePopup}>
                        <span aria-hidden="true">&times;</span>
                    </button> */}
        </div>
      </div>

      <div className="row">
        <div className="col-md-12 text-right mt-30">
          <button className="btn btn-sm btn-primary" onClick={props.closePopup}>
            OK
          </button>
        </div>
      </div>
    </ReactModal>
  );
};

export default PopupCabinType;
