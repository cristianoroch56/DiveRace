import React, { useState } from 'react';
import 'react-dates/initialize';
import { DateRangePicker } from 'react-dates';
import isSameDay from 'react-dates/lib/utils/isSameDay';
import 'react-dates/lib/css/_datepicker.css';
import moment from 'moment';
const InputDateRange = ({
  startDate,
  endDate,
  onChange,
  onClose,
  highlightedDates
}) => {
  const [focusedInput, setFocusedInput] = useState(null);

  return (
    <div
      className="input-date-range"
    >
      <DateRangePicker
        startDate={startDate}
        endDate={endDate}
        minDate={moment()}
        
        startDateId="startDate"
        endDateId="endDate"
        focusedInput={focusedInput}
        onDatesChange={onChange}
        onClose={onClose}
        onFocusChange={(input) => {
          setFocusedInput(input);
        }}
        displayFormat="DD/MM/YYYY"
        showDefaultInputIcon
        showClearDates
        isOutsideRange={() => false}
        isDayHighlighted={day1 => highlightedDates.some(day2 => isSameDay(day1, moment(day2)))}
        hideKeyboardShortcutsPanel
        
        orientation={window.matchMedia("(max-width: 700px)").matches ? 'vertical' : 'horizontal'}
        large
      />
    </div>
  );
};

export default InputDateRange;
