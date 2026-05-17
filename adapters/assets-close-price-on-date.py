import json
from datetime import datetime
from datetime import timedelta
import yfinance as yf
import numpy as np
import sys

JsonContent = '{"2026-03-27":["BCIA11.SA"],"2026-04-01":["BBDC4.SA"],"2026-04-13":["B3SA3.SA"],"2026-04-15":["HGBS11.SA","HGLG11.SA","KNRI11.SA","NDIV11.SA","XPLG11.SA"],"2026-04-20":["CPTS11.SA"],"2026-04-24":["ALZR11.SA","XPML11.SA"],"2026-04-30":["BBDC4.SA","BCIA11.SA"],"2026-05-04":["BBDC4.SA"]}'
AssetsByDate = json.loads(JsonContent)

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

