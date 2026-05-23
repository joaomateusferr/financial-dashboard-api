from pathlib import Path
import json
from datetime import datetime
from datetime import timedelta
import yfinance as yf
import numpy as np
import sys

try:

    FilePath = sys.argv[1] if len(sys.argv) > 1 and sys.argv[1] else None

    if not FilePath :
        raise Exception("File path not specified!")

    FilePath = Path(FilePath)

    if not FilePath.exists():
       raise Exception("Path or file does not exist!")

    if not FilePath.is_file():
       raise Exception("Path does not lead to a file!")

    if FilePath.suffix.lower() != '.json':
        raise Exception("File is not json!")

    Content = FilePath.read_text()
    AssetsByDate = json.loads(Content)

    Result = {}

    for Date, Assets in AssetsByDate.items() :

        Date = datetime.strptime(Date, "%Y-%m-%d")
        DateStart = Date.strftime("%Y-%m-%d")
        DateEnd = Date + timedelta(days=1)
        DateEnd = DateEnd.strftime("%Y-%m-%d")

        Data = yf.download(Assets, start=DateStart, end=DateEnd, auto_adjust=True, progress=False)
        Data = Data['Close'].to_numpy()[0]

        Result[DateStart] = {}

        if isinstance(Data, np.ndarray) :

            for Index in range(len(Assets)):

                Result[DateStart][Assets[Index]] = Data[Index]

        else :

            Result[DateStart][Assets[0]] = Data


    print(json.dumps(Result))

except Exception as ex:

    print("Something went wrong ...\n"+ str(ex))
    sys.exit(1)
